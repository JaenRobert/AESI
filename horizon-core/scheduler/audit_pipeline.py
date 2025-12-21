from horizon_core.api.routes_analytics import analytics
from horizon_core.scheduler.adaptive_profiles import create_adaptive_profile
from horizon_core.scheduler.dependency_graph import validate_pipeline
from horizon_core.security.policies import load_policies
from horizon_core.contracts.validator import validate_contract
from horizon_core.scheduler.router import get_pipeline

def run_audit_pipeline(run: dict):
    results = {}
    # Contract validation
    results["contract"] = validate_contract(run)
    # Dependency validation
    _, pipeline = get_pipeline(run["metadata"]["profile"])
    results["dependencies"] = validate_pipeline(pipeline)
    # Policy snapshot
    results["policies"] = load_policies()
    return results
