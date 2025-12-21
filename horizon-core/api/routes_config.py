from fastapi import APIRouter
from horizon_core.scheduler.router import load_pipeline_config

router = APIRouter()

@router.get("/config/pipeline")
def get_pipeline_config():
    return {"pipeline": load_pipeline_config()}
