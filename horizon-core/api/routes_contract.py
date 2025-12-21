from fastapi import APIRouter
from horizon_core.storage.sqlite_store import get_run

router = APIRouter()

@router.get("/contract/{run_id}")
def export_contract(run_id: str):
    run = get_run(run_id)
    return run.get("contract")

@router.get("/contract/validate/{run_id}")
def validate(run_id: str):
    from horizon_core.contracts.validator import validate_contract
    run = get_run(run_id)
    return validate_contract(run)
