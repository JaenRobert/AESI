NODE_DEPENDENCIES = {
    "reflex": [],
    "mistral": ["reflex"],
    "perplexity": ["reflex"],
    "claude_veto": ["mistral", "perplexity"],
    "sigma": ["claude_veto"],
    "llama": ["sigma"],
    "hafted": ["llama"],
    "sentinel": ["reflex", "mistral", "perplexity", "claude_veto", "sigma", "llama", "hafted"],
    "copilot": ["reflex"]
}

def get_dependencies(node: str):
    return NODE_DEPENDENCIES.get(node, [])

def validate_pipeline(pipeline: list[str]):
    missing = {}
    for node in pipeline:
        deps = get_dependencies(node)
        for d in deps:
            if d not in pipeline:
                missing.setdefault(node, []).append(d)
    return missing
