# horizon-core/storage/db.py
import sqlite3
from pathlib import Path

DB_PATH = Path(__file__).parent / "horizon.db"

def get_connection():
    conn = sqlite3.connect(DB_PATH)
    conn.row_factory = sqlite3.Row
    return conn

def init_db():
    conn = get_connection()
    cur = conn.cursor()

    # Tabell för körningar
    cur.execute("""
        CREATE TABLE IF NOT EXISTS runs (
            run_id TEXT PRIMARY KEY,
            status TEXT,
            final_hash TEXT
        )
    """)

    # Tabell för trace-steg
    cur.execute("""
        CREATE TABLE IF NOT EXISTS trace_steps (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            run_id TEXT,
            step_index INTEGER,
            node TEXT,
            output TEXT,
            hash TEXT,
            start_time REAL,
            end_time REAL,
            duration REAL,
            health TEXT,
            error TEXT,
            FOREIGN KEY(run_id) REFERENCES runs(run_id)
        )
    """)

    conn.commit()
    conn.close()
