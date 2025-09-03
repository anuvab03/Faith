import streamlit as st
import streamlit.components.v1 as components
import joblib, os, pandas as pd
from apify_client import ApifyClient
from dotenv import load_dotenv

st.set_page_config(page_title="FAITH", page_icon="📷", layout="wide")

@st.cache_resource
def load_model():
    model_path = os.path.join(os.path.dirname(__file__), "svm_model.pkl")
    return joblib.load(model_path)

# ---------------- Sidebar (instructions stay) ----------------
st.sidebar.header("ℹ️ About")
st.sidebar.info(
    "This tool uses machine learning to detect fake Instagram accounts "
    "based on profile features like username patterns, bio length, "
    "follower ratios, and more."
)

st.sidebar.header("🔍 How it works")
st.sidebar.markdown("""
1. Enter an Instagram username in the dashboard  
2. The system fetches profile data  
3. ML model analyzes features  
4. You get prediction: **Real or Fake**
""")

# ---------------- Backend functions (unchanged) ----------------
def apifyreq(username):
    load_dotenv()
    api_key = os.getenv("API_TOKEN")
    client = ApifyClient(api_key)
    run_input = {"usernames": [username]}
    actor = os.getenv("ACTOR")
    run = client.actor(actor).call(run_input=run_input)
    dataset_id = run["defaultDatasetId"]
    return client.dataset(dataset_id).list_items().items

def preprocess_data(dataset):
    if isinstance(dataset, list) and len(dataset) > 0:
        dataset = dataset[0]
    features = {
        "ProfilePic": 1 if dataset.get("profile_pic") else 0,
        "UsernameLength": len(dataset.get("username", "")),
        "FullnameWords": len(dataset.get("fullName", "").split()),
        "FullnameLength": len(dataset.get("fullName", "")),
        "name==username": int(dataset.get("fullName", "").lower() == dataset.get("username", "").lower()),
        "DescriptionLength": len(dataset.get("biography", "")),
        "private": int(dataset.get("private", False)),
        "posts": int(dataset.get("postsCount", 0))
    }
    return pd.DataFrame([features])

def predict_fake_account(model, processed_data):
    return bool(model.predict(processed_data)[0])

# ---------------- Prediction Handling ----------------
query_params = st.experimental_get_query_params()
if "username" in query_params:
    username = query_params["username"][0]
    st.subheader(f"Prediction for @{username}")

    try:
        dataset = apifyreq(username)
        if not dataset:
            st.error("❌ Username not found or profile is private.")
        else:
            processed_data = preprocess_data(dataset)
            model = load_model()
            prediction = predict_fake_account(model, processed_data)

            if prediction:
                st.error("🚨 This account appears to be **FAKE**")
            else:
                st.success("✅ This account appears to be **REAL**")

            with st.expander("📊 Processed Features"):
                st.dataframe(processed_data)

    except Exception as e:
        st.error(f"⚠️ Error: {e}")

# ---------------- Inject Pixel-Perfect Dashboard ----------------
with open("dashboard.html", "r", encoding="utf-8") as f:
    html_code = f.read()

# Replace fetch → redirect to query param
html_code = html_code.replace(
    "fetch('/predict'",
    "window.location = '?username=' + document.getElementById('username').value; //"
)

# Force dark mode styles
dark_override = """
<style>
body, html {
    background-color: #0e1117 !important;
    color: #fafafa !important;
}
nav, .dashboard, .prediction-form {
    background-color: #1e1e1e !important;
    color: #fafafa !important;
}
input, button {
    background-color: #2b2b2b !important;
    color: #fafafa !important;
    border: 1px solid #444 !important;
}
</style>
"""

components.html(dark_override + html_code, height=900, scrolling=True)
