import time
import random

def mock_slow(payload):
    time.sleep(random.uniform(0.5, 2.0))
    return {"node": "mock_slow", "output": "slow response"}

def mock_error(payload):
    raise Exception("Simulated node failure")

def mock_random(payload):
    if random.random() < 0.3:
        raise Exception("Random failure")
    return {"node": "mock_random", "output": "ok"}
