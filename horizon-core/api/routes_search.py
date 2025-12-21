from fastapi import APIRouter, Query
from horizon_core.storage.db import get_connection
import json

router = APIRouter()

@router.get("/search")
def search_runs(
    tag: str | None = None,
    profile: str | None = None,
    text: str | None = None,
    has_error: bool | None = None
):
    conn = get_connection()
    cur = conn.cursor()

    query = "SELECT * FROM runs"
    conditions = []
    params = []

    # Taggfilter
    if tag:
        conditions.append("tags LIKE ?")
        params.append(f"%{tag}%")

    # Profilfilter (metadata)
    if profile:
        conditions.append("metadata LIKE ?")
        params.append(f'%"profile": "{profile}"%')

    # Textmatchning i trace
    if text:
        query = """
            SELECT DISTINCT runs.*
            FROM runs
            JOIN trace_steps ON runs.run_id = trace_steps.run_id
            WHERE trace_steps.output LIKE ?
        """
        params = [f"%{text}%"]

    # Nodfel
    if has_error is True:
        conditions.append("run_id IN (SELECT run_id FROM trace_steps WHERE health = 'error')")
    elif has_error is False:
        conditions.append("run_id NOT IN (SELECT run_id FROM trace_steps WHERE health = 'error')")

    # Slå ihop villkor
    if conditions:
        query += " WHERE " + " AND ".join(conditions)

    cur.execute(query, params)
    rows = cur.fetchall()

    results = []
    for r in rows:
        results.append({
            "run_id": r["run_id"],
            "status": r["status"],
            "final_hash": r["final_hash"],
            "tags": json.loads(r["tags"]) if r["tags"] else [],
            "metadata": json.loads(r["metadata"]) if r["metadata"] else {}
        })

    return results
