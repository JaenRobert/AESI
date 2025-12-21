# horizon-core/storage/sqlite_store.py
from typing import List, Dict
from .db import get_connection

def save_run(run_id: str, trace: List[Dict], final_hash: str):
    conn = get_connection()
    cur = conn.cursor()

    # Spara run
    cur.execute(
        "INSERT OR REPLACE INTO runs (run_id, status, final_hash) VALUES (?, ?, ?)",
        (run_id, "completed", final_hash)
    )

    # Spara trace-steg
    for index, step in enumerate(trace, start=1):
        cur.execute(
            """
            INSERT INTO trace_steps (run_id, step_index, node, output, hash, start_time, end_time, duration, health, error)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            """,
            (
                run_id,
                index,
                step["node"],
                step["output"],
                step["hash"],
                step.get("start_time"),
                step.get("end_time"),
                step.get("duration"),
                step.get("health"),
                step.get("error"),
            )
        )

    conn.commit()
    conn.close()

def get_run(run_id: str):
    conn = get_connection()
    cur = conn.cursor()

    # Hämta run
    cur.execute("SELECT * FROM runs WHERE run_id = ?", (run_id,))
    run_row = cur.fetchone()

    if not run_row:
        return None

    # Hämta trace
    cur.execute(
        "SELECT step_index, node, output, hash, start_time, end_time, duration, health, error FROM trace_steps WHERE run_id = ? ORDER BY step_index ASC",
        (run_id,)
    )
    trace_rows = cur.fetchall()

    trace = [
        {
            "node": row["node"],
            "output": row["output"],
            "hash": row["hash"],
            "start_time": row["start_time"],
            "end_time": row["end_time"],
            "duration": row["duration"],
            "health": row["health"],
            "error": row["error"],
        }
        for row in trace_rows
    ]

    return {
        "run_id": run_id,
        "status": run_row["status"],
        "final_hash": run_row["final_hash"],
        "trace": trace
    }
