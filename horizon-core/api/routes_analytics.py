from fastapi import APIRouter
from horizon_core.storage.db import get_connection

router = APIRouter()

@router.get("/analytics")
def analytics():
            # Prestandapoäng per nod
            # Hämta latens
            cur.execute("""
                SELECT node, AVG(duration) AS avg_duration
                FROM trace_steps
                WHERE duration IS NOT NULL
                GROUP BY node
            """)
            latency_rows = cur.fetchall()

            # Hämta fel
            cur.execute("""
                SELECT node, COUNT(*) AS errors
                FROM trace_steps
                WHERE health = 'error'
                GROUP BY node
            """)
            error_rows = cur.fetchall()
            error_map = {row["node"]: row["errors"] for row in error_rows}

            performance_scores = []
            for row in latency_rows:
                node = row["node"]
                avg_dur = row["avg_duration"]
                errors = error_map.get(node, 0)
                score = (1 / (avg_dur + 0.001)) - errors
                performance_scores.append({
                    "node": node,
                    "avg_duration": avg_dur,
                    "errors": errors,
                    "score": score
                })

            recommended_order = sorted(
                performance_scores,
                key=lambda x: x["score"],
                reverse=True
            )
            recommended_pipeline = [x["node"] for x in recommended_order]
        # Nodlatens
        cur.execute("""
            SELECT node, AVG(duration) AS avg_duration
            FROM trace_steps
            WHERE duration IS NOT NULL
            GROUP BY node
        """)
        node_latency = [
            {"node": row["node"], "avg_duration": row["avg_duration"]}
            for row in cur.fetchall()
        ]
    conn = get_connection()
    cur = conn.cursor()

    # Antal körningar
    cur.execute("SELECT COUNT(*) AS count FROM runs")
    total_runs = cur.fetchone()["count"]

    # Veto-frekvens (Claude)
    cur.execute("""
        SELECT COUNT(*) AS count
        FROM trace_steps
        WHERE node = 'claude_veto'
        AND output LIKE '%deny%'
    """)
    veto_count = cur.fetchone()["count"]

    # Sentinel-avvikelser
    cur.execute("""
        SELECT COUNT(*) AS count
        FROM trace_steps
        WHERE node = 'sentinel'
        AND output LIKE '%avvikelse%'
    """)
    sentinel_issues = cur.fetchone()["count"]

    # Genomsnittligt antal steg per run
    cur.execute("""
        SELECT AVG(step_count) AS avg_steps
        FROM (
            SELECT run_id, COUNT(*) AS step_count
            FROM trace_steps
            GROUP BY run_id
        )
    """)
    avg_steps = cur.fetchone()["avg_steps"]

    # Genomsnittlig nodtid
    cur.execute("""
        SELECT AVG(duration) AS avg_node_time
        FROM trace_steps
        WHERE duration IS NOT NULL
    """)
    avg_node_time = cur.fetchone()["avg_node_time"]

    # Körningar per dag
    cur.execute("""
        SELECT DATE(timestamp, 'unixepoch') AS day, COUNT(*) AS count
        FROM runs
        GROUP BY day
    """)
    runs_per_day = [
        {"day": row["day"], "count": row["count"]}
        for row in cur.fetchall()
    ]

    # Fel per dag
    cur.execute("""
        SELECT DATE(runs.timestamp, 'unixepoch') AS day, COUNT(*) AS count
        FROM runs
        JOIN trace_steps ON runs.run_id = trace_steps.run_id
        WHERE trace_steps.health = 'error'
        GROUP BY day
    """)
    errors_per_day = [
        {"day": row["day"], "count": row["count"]}
        for row in cur.fetchall()
    ]

    # Nodaktivitet
    cur.execute("""
        SELECT node, COUNT(*) AS count
        FROM trace_steps
        GROUP BY node
    """)
    node_activity = [
        {"node": row["node"], "count": row["count"]}
        for row in cur.fetchall()
    ]

    # Nodfel
    cur.execute("""
        SELECT node, COUNT(*) AS count
        FROM trace_steps
        WHERE health = 'error'
        GROUP BY node
    """)
    node_errors = [
        {"node": row["node"], "count": row["count"]}
        for row in cur.fetchall()
    ]

    return {
        "total_runs": total_runs,
        "veto_count": veto_count,
        "sentinel_issues": sentinel_issues,
        "avg_steps": avg_steps,
        "avg_node_time": avg_node_time,
        "runs_per_day": runs_per_day,
        "errors_per_day": errors_per_day,
        "node_activity": node_activity,
        "node_errors": node_errors,
        "node_latency": node_latency,
        "performance_scores": performance_scores,
        "recommended_pipeline": recommended_pipeline
    }
