from fastapi import APIRouter
from horizon_core.storage.sqlite_store import get_run
from horizon_core.aesi.aesi_mapping import map_run_to_aesi

router = APIRouter()

@router.get("/aesi/{run_id}")
def export_aesi(run_id: str):
    run = get_run(run_id)
    return map_run_to_aesi(run)
