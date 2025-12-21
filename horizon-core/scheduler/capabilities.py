import json

def load_capabilities():
    return json.load(open("horizon-core/config/capabilities.json"))

def validate_capabilities(pipeline):
    caps = load_capabilities()
    unsupported = {}
    for node in pipeline:
        if node not in caps:
            unsupported[node] = "No capability declaration"
    return unsupported
