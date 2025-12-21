-- Migration: Add timestamp column to runs
ALTER TABLE runs ADD COLUMN timestamp REAL;
