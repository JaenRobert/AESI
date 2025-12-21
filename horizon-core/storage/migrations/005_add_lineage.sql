-- Migration: Add parent_run_id and lineage columns to runs
ALTER TABLE runs ADD COLUMN parent_run_id TEXT;
ALTER TABLE runs ADD COLUMN lineage TEXT;
