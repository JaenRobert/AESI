from fastapi import APIRouter, Request
from horizon_core.scheduler.pipeline_simulator import simulate_pipeline
from horizon_core.scheduler.router import get_pipeline

router = APIRouter()

@router.post("/simulate")
def simulate(profile: str):
    _, pipeline = get_pipeline(profile)
    return simulate_pipeline(pipeline)
