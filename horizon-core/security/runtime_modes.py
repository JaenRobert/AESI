import json

def load_runtime_mode(mode: str | None):
    data = json.load(open("horizon-core/config/runtime_modes.json"))
    if mode is None:
        mode = data["default_mode"]
    return data["modes"][mode]
