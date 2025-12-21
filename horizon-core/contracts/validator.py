def validate_contract(run: dict):
    contract = run.get("contract", {})
    violations = []

    # Policy: max_duration_seconds
    max_dur = contract["policies"].get("max_duration_seconds")
    if max_dur:
        for step in run["trace"]:
            if step["duration"] and step["duration"] > max_dur:
                violations.append({
                    "node": step["node"],
                    "type": "duration_violation",
                    "value": step["duration"]
                })

    return violations
