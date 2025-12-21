from fastapi import APIRouter
from horizon_core.scheduler.adaptive_profiles import create_adaptive_profile
from horizon_core.api.routes_analytics import analytics

router = APIRouter()

@router.post("/profiles/adaptive/{name}")
def create_profile(name: str):
    data = analytics()
    recommended = data["recommended_pipeline"]
    profile = create_adaptive_profile(name, recommended)
    return {"profile": name, "nodes": profile}
