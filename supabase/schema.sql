-- ============================================================
-- KEEPTION VAULT â€” Supabase Schema
-- Run this in your Supabase SQL Editor
-- ============================================================

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pg_trgm"; -- for full-text search

-- ============================================================
-- PROFILES
-- ============================================================
CREATE TABLE profiles (
  id         UUID PRIMARY KEY REFERENCES auth.users(id) ON DELETE CASCADE,
  email      TEXT NOT NULL,
  username   TEXT UNIQUE,
  avatar_url TEXT,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE profiles ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Users can view own profile"   ON profiles FOR SELECT USING (auth.uid() = id);
CREATE POLICY "Users can update own profile" ON profiles FOR UPDATE USING (auth.uid() = id);
CREATE POLICY "Users can insert own profile" ON profiles FOR INSERT WITH CHECK (auth.uid() = id);

-- Auto-create profile on signup
CREATE OR REPLACE FUNCTION handle_new_user()
RETURNS TRIGGER AS $$
BEGIN
  INSERT INTO profiles (id, email)
  VALUES (NEW.id, NEW.email);
  RETURN NEW;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

CREATE TRIGGER on_auth_user_created
  AFTER INSERT ON auth.users
  FOR EACH ROW EXECUTE FUNCTION handle_new_user();

-- ============================================================
-- ITEMS (central table)
-- ============================================================
CREATE TYPE item_type AS ENUM ('file', 'note', 'link');

CREATE TABLE items (
  id          UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id     UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
  type        item_type NOT NULL,
  title       TEXT NOT NULL,
  description TEXT,
  is_starred  BOOLEAN DEFAULT FALSE,
  is_trashed  BOOLEAN DEFAULT FALSE,
  created_at  TIMESTAMPTZ DEFAULT NOW(),
  updated_at  TIMESTAMPTZ DEFAULT NOW(),
  search_vec  TSVECTOR GENERATED ALWAYS AS (
    to_tsvector('english', coalesce(title, '') || ' ' || coalesce(description, ''))
  ) STORED
);

ALTER TABLE items ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Users access own items" ON items FOR ALL USING (auth.uid() = user_id);
CREATE INDEX items_search_idx ON items USING GIN(search_vec);
CREATE INDEX items_user_id_idx ON items(user_id);

-- Auto-update updated_at
CREATE OR REPLACE FUNCTION update_updated_at()
RETURNS TRIGGER AS $$
BEGIN NEW.updated_at = NOW(); RETURN NEW; END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER items_updated_at
  BEFORE UPDATE ON items
  FOR EACH ROW EXECUTE FUNCTION update_updated_at();

-- ============================================================
-- NOTES
-- ============================================================
CREATE TABLE notes (
  id         UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  item_id    UUID NOT NULL REFERENCES items(id) ON DELETE CASCADE,
  content    TEXT,
  search_vec TSVECTOR GENERATED ALWAYS AS (
    to_tsvector('english', coalesce(content, ''))
  ) STORED
);

ALTER TABLE notes ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Users access own notes" ON notes FOR ALL
  USING (EXISTS (SELECT 1 FROM items WHERE items.id = notes.item_id AND items.user_id = auth.uid()));
CREATE INDEX notes_search_idx ON notes USING GIN(search_vec);

-- ============================================================
-- LINKS
-- ============================================================
CREATE TABLE links (
  id            UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  item_id       UUID NOT NULL REFERENCES items(id) ON DELETE CASCADE,
  url           TEXT NOT NULL,
  preview_image TEXT,
  preview_title TEXT,
  preview_desc  TEXT
);

ALTER TABLE links ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Users access own links" ON links FOR ALL
  USING (EXISTS (SELECT 1 FROM items WHERE items.id = links.item_id AND items.user_id = auth.uid()));

-- ============================================================
-- FILES
-- ============================================================
CREATE TABLE files (
  id        UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  item_id   UUID NOT NULL REFERENCES items(id) ON DELETE CASCADE,
  file_url  TEXT NOT NULL,
  file_name TEXT NOT NULL,
  file_type TEXT,
  size      BIGINT,
  bucket    TEXT DEFAULT 'keeption-files'
);

ALTER TABLE files ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Users access own files" ON files FOR ALL
  USING (EXISTS (SELECT 1 FROM items WHERE items.id = files.item_id AND items.user_id = auth.uid()));

-- ============================================================
-- FOLDERS
-- ============================================================
CREATE TABLE folders (
  id         UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id    UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
  name       TEXT NOT NULL,
  parent_id  UUID REFERENCES folders(id) ON DELETE CASCADE,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

ALTER TABLE folders ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Users access own folders" ON folders FOR ALL USING (auth.uid() = user_id);

-- ============================================================
-- ITEM_FOLDERS (many-to-many)
-- ============================================================
CREATE TABLE item_folders (
  id        UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  item_id   UUID NOT NULL REFERENCES items(id) ON DELETE CASCADE,
  folder_id UUID NOT NULL REFERENCES folders(id) ON DELETE CASCADE,
  UNIQUE(item_id, folder_id)
);

ALTER TABLE item_folders ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Users access own item_folders" ON item_folders FOR ALL
  USING (EXISTS (SELECT 1 FROM items WHERE items.id = item_folders.item_id AND items.user_id = auth.uid()));

-- ============================================================
-- TAGS
-- ============================================================
CREATE TABLE tags (
  id      UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  user_id UUID NOT NULL REFERENCES auth.users(id) ON DELETE CASCADE,
  name    TEXT NOT NULL,
  color   TEXT DEFAULT '#00f5a0',
  UNIQUE(user_id, name)
);

ALTER TABLE tags ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Users access own tags" ON tags FOR ALL USING (auth.uid() = user_id);

-- ============================================================
-- ITEM_TAGS (many-to-many)
-- ============================================================
CREATE TABLE item_tags (
  id      UUID PRIMARY KEY DEFAULT uuid_generate_v4(),
  item_id UUID NOT NULL REFERENCES items(id) ON DELETE CASCADE,
  tag_id  UUID NOT NULL REFERENCES tags(id) ON DELETE CASCADE,
  UNIQUE(item_id, tag_id)
);

ALTER TABLE item_tags ENABLE ROW LEVEL SECURITY;
CREATE POLICY "Users access own item_tags" ON item_tags FOR ALL
  USING (EXISTS (SELECT 1 FROM items WHERE items.id = item_tags.item_id AND items.user_id = auth.uid()));

-- ============================================================
-- STORAGE BUCKET (run in Supabase dashboard or via API)
-- ============================================================
-- INSERT INTO storage.buckets (id, name, public) VALUES ('keeption-files', 'keeption-files', false);

-- Storage RLS: users can only access their own folder
-- CREATE POLICY "User file access" ON storage.objects FOR ALL
--   USING (bucket_id = 'keeption-files' AND auth.uid()::text = (storage.foldername(name))[1]);

-- ============================================================
-- FULL-TEXT SEARCH FUNCTION
-- ============================================================
CREATE OR REPLACE FUNCTION search_vault(query TEXT, uid UUID)
RETURNS TABLE (
  item_id     UUID,
  type        item_type,
  title       TEXT,
  description TEXT,
  rank        REAL
) AS $$
BEGIN
  RETURN QUERY
  SELECT i.id, i.type, i.title, i.description,
         ts_rank(i.search_vec, to_tsquery('english', query)) AS rank
  FROM items i
  WHERE i.user_id = uid
    AND i.is_trashed = FALSE
    AND i.search_vec @@ to_tsquery('english', query)
  UNION
  SELECT i.id, i.type, i.title, i.description,
         ts_rank(n.search_vec, to_tsquery('english', query)) AS rank
  FROM notes n
  JOIN items i ON i.id = n.item_id
  WHERE i.user_id = uid
    AND i.is_trashed = FALSE
    AND n.search_vec @@ to_tsquery('english', query)
  ORDER BY rank DESC;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;
