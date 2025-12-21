def select_profile(text: str, analytics_data: dict):
    # Enkel heuristik
    if "audit" in text.lower():
        return "audit"
    if "snabb" in text.lower() or "quick" in text.lower():
        return "snabb"
    # Prestandabaserad fallback
    return analytics_data.get("recommended_pipeline", None)
