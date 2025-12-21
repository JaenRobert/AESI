TENANTS = {}

def get_tenant(tenant_id: str):
    if tenant_id not in TENANTS:
        TENANTS[tenant_id] = {"runs": []}
    return TENANTS[tenant_id]
