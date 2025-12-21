from horizon_core.api.routes_analytics import analytics
from horizon_core.scheduler.adaptive_profiles import create_adaptive_profile

def auto_tune():
    data = analytics()
    recommended = data["recommended_pipeline"]
    profile_name = "auto_tuned"
    create_adaptive_profile(profile_name, recommended)
    return profile_name, recommended
