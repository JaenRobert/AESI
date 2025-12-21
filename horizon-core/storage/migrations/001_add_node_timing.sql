-- SQLite migration for node timing fields
ALTER TABLE trace_steps ADD COLUMN start_time REAL;
ALTER TABLE trace_steps ADD COLUMN end_time REAL;
ALTER TABLE trace_steps ADD COLUMN duration REAL;
