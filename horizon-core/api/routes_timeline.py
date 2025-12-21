from fastapi import APIRouter
from horizon_core.storage.db import get_connection
import json

router = APIRouter()

@router.get("/timeline")
def timeline():
    conn = get_connection()
    cur = conn.cursor()

    cur.execute("SELECT * FROM runs ORDER BY timestamp ASC")
    rows = cur.fetchall()

    timeline = []
    for r in rows:
        timeline.append({
            "run_id": r["run_id"],
            "timestamp": r["timestamp"],
            "tags": json.loads(r["tags"]) if r["tags"] else [],
            "metadata": json.loads(r["metadata"]) if r["metadata"] else {},
            "status": r["status"]
        })

    return timeline
