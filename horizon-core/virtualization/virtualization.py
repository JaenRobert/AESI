class VirtualNode:
    def __init__(self, node_name, func):
        self.node_name = node_name
        self.func = func

    def execute(self, payload):
        # Minimal virtualization PoC
        capsule = {
            "input": payload,
            "env": {"virtualized": True},
        }
        output = self.func(capsule["input"])
        return {
            "node": self.node_name,
            "output": output,
            "capsule": capsule
        }
