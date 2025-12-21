from fastapi import APIRouter
from horizon_core.scheduler.dependency_graph import validate_pipeline
from horizon_core.scheduler.router import get_pipeline

router = APIRouter()

@router.get("/pipeline/validate")
def validate(profile: str | None = None):
    _, pipeline = get_pipeline(profile)
    missing = validate_pipeline(pipeline)
    return {"pipeline": pipeline, "missing_dependencies": missing}
