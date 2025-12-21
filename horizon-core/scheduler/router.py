# horizon-core/scheduler/router.py

import time

from horizon_core.nodes.node_registry import NODE_REGISTRY
import json
from pathlib import Path

CONFIG_PATH = Path("horizon-core/config/pipeline_config.json")


def load_pipeline_data():
    with open(CONFIG_PATH, "r") as f:
        return json.load(f)

def get_pipeline(profile: str | None = None):
    data = load_pipeline_data()
    if profile is None:
        profile = data.get("default_profile", "full")

    profiles = data["profiles"]
    if profile not in profiles:
        # fallback till default om profil saknas/ogiltig
        profile = data.get("default_profile", "full")

    return profile, profiles[profile]

def safe_call_node(node_name: str, func, payload: dict) -> dict:
    try:
        result = {
            "ok": True,
            "node_output": func(payload),
            "error": None,
        }
    except Exception as e:
        result = {
            "ok": False,
            "node_output": {
                "node": node_name,
                "output": f"[ERROR] Nod {node_name} kastade ett undantag.",
                "meta": {"error_type": type(e).__name__}
            },
            "error": str(e),
        }

    # Uppdatera nodstatus i registry
    if result["ok"]:
        NODE_REGISTRY[node_name]["status"] = "ok"
        NODE_REGISTRY[node_name]["last_error"] = None
    else:
        NODE_REGISTRY[node_name]["status"] = "error"
        NODE_REGISTRY[node_name]["last_error"] = result["error"]
    return result


# def determine_path(text: str):
#     return ["reflex", "mistral", ...]
# Nu:
def determine_path(text: str, profile: str | None = None):
    active_profile, path = get_pipeline(profile)
    return active_profile, path
