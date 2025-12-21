def simulate_pipeline(pipeline: list[str]):
    simulated = []
    for node in pipeline:
        simulated.append({
            "node": node,
            "expected_latency": "unknown",
            "expected_error_rate": "unknown",
            "status": "simulated"
        })
    return simulated
