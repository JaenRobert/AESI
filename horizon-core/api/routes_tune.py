from fastapi import APIRouter
from horizon_core.scheduler.auto_tuner import auto_tune

router = APIRouter()

@router.post("/tune")
def tune():
    name, pipeline = auto_tune()
    return {"profile": name, "pipeline": pipeline}
