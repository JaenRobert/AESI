def map_run_to_aesi(run: dict):
    return {
        "aesi_version": "1.0-poc",
        "run_id": run["run_id"],
        "profile": run["metadata"].get("profile"),
        "contract": run.get("contract"),
        "lineage": run.get("lineage"),
        "nodes": [
            {
                "node": step["node"],
                "hash": step["hash"],
                "health": step["health"],
                "duration": step["duration"],
                "output": step["output"]
            }
            for step in run["trace"]
        ]
    }
