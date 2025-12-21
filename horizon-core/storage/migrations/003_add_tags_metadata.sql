-- Migration: Add tags and metadata columns to runs
ALTER TABLE runs ADD COLUMN tags TEXT;
ALTER TABLE runs ADD COLUMN metadata TEXT;
