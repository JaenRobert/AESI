from fastapi import APIRouter
from horizon_core.storage.sqlite_store import get_run

router = APIRouter()

@router.get("/compare/{run_a}/{run_b}")
def compare_runs(run_a: str, run_b: str):
    A = get_run(run_a)
    B = get_run(run_b)

    if not A or not B:
        return {"error": "One or both runs not found"}

    # Nodsekvenser
    seq_a = [s["node"] for s in A["trace"]]
    seq_b = [s["node"] for s in B["trace"]]

    # Hashkedjor
    hashes_a = [s["hash"] for s in A["trace"]]
    hashes_b = [s["hash"] for s in B["trace"]]

    # Sentinel-status
    sentinel_a = next((s for s in A["trace"] if s["node"] == "sentinel"), None)
    sentinel_b = next((s for s in B["trace"] if s["node"] == "sentinel"), None)

    # Diff per nod
    node_diffs = []
    for step_a, step_b in zip(A["trace"], B["trace"]):
        node_diffs.append({
            "node": step_a["node"],
            "output_diff": step_a["output"] != step_b["output"],
            "hash_diff": step_a["hash"] != step_b["hash"],
            "health_diff": step_a["health"] != step_b["health"]
        })

    return {
        "run_a": run_a,
        "run_b": run_b,
        "sequence_diff": seq_a != seq_b,
        "hashchain_diff": hashes_a != hashes_b,
        "sentinel_diff": sentinel_a["output"] != sentinel_b["output"] if sentinel_a and sentinel_b else None,
        "node_diffs": node_diffs
    }
