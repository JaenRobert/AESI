def create_capsule(node_name: str, payload: dict):
    return {
        "node": node_name,
        "payload": payload,
        "environment": {
            "virtualized": True,
            "capsule_version": "1.0"
        }
    }
