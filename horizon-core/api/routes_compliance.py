from fastapi import APIRouter, Request
from horizon_core.storage.sqlite_store import get_run
from horizon_core.security.policies import load_policies
from horizon_core.scheduler.dependency_graph import NODE_ISOLATION, validate_pipeline
from horizon_core.scheduler.router import get_pipeline
from horizon_core.scheduler.audit_pipeline import run_audit_pipeline
from horizon_core.api.routes_analytics import analytics

router = APIRouter()

@router.get("/dashboard/run/{run_id}/compliance")
def dashboard_compliance(request: Request, run_id: str):
    run = get_run(run_id)
    policies = load_policies()
    audit = run_audit_pipeline(run)
    isolation = NODE_ISOLATION
    return templates.TemplateResponse(
        "compliance.html",
        {
            "request": request,
            "contract": run["contract"],
            "policies": policies,
            "audit_results": audit,
            "isolation": isolation
        }
    )

@router.get("/bundle/{run_id}")
def export_bundle(run_id: str):
    run = get_run(run_id)
    audit = run_audit_pipeline(run)
    policies = load_policies()
    deps = validate_pipeline(get_pipeline(run["metadata"]["profile"])[1])
    analytics_data = analytics()
    return {
        "run": run,
        "audit_results": audit,
        "policies": policies,
        "dependencies": deps,
        "recommended_pipeline": analytics_data["recommended_pipeline"]
    }
