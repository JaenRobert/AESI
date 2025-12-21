from fastapi import APIRouter, Response
from horizon_core.storage.sqlite_store import get_run
import json

router = APIRouter()

@router.get("/export/{run_id}.json")
def export_json(run_id: str):
    run = get_run(run_id)
    if not run:
        return {"error": "Run not found"}

    return Response(
        content=json.dumps(run, ensure_ascii=False, indent=2),
        media_type="application/json"
    )

@router.get("/export/{run_id}.ndjson")
def export_ndjson(run_id: str):
    run = get_run(run_id)
    if not run:
        return {"error": "Run not found"}

    lines = []
    for step in run["trace"]:
        lines.append(json.dumps(step, ensure_ascii=False))

    ndjson = "\n".join(lines)

    return Response(
        content=ndjson,
        media_type="application/x-ndjson"
    )
