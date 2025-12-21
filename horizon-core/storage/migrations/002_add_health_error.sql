-- Migration: Add health and error columns to trace_steps
ALTER TABLE trace_steps ADD COLUMN health TEXT;
ALTER TABLE trace_steps ADD COLUMN error TEXT;
