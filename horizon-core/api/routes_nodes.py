from fastapi import APIRouter
from horizon_core.nodes.node_registry import NODE_REGISTRY

router = APIRouter()

@router.get("/nodes")
def list_nodes():
    return NODE_REGISTRY
