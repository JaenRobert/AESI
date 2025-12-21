import json
from jsonschema import validate

def validate_pipeline_config(config):
    schema = json.load(open("horizon-core/config/pipeline_schema.json"))
    validate(config, schema)
