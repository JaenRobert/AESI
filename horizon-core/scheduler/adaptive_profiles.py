import json
from pathlib import Path

CONFIG_PATH = Path("horizon-core/config/pipeline_config.json")

def load_config():
    with open(CONFIG_PATH, "r") as f:
        return json.load(f)

def save_config(data):
    with open(CONFIG_PATH, "w") as f:
        json.dump(data, f, indent=2, ensure_ascii=False)

def create_adaptive_profile(name: str, recommended_pipeline: list[str]):
    data = load_config()
    data["profiles"][name] = recommended_pipeline
    save_config(data)
    return data["profiles"][name]
