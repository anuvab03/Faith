import random
import streamlit as st
import joblib
import pandas as pd
from apify_client import ApifyClient
import os
from dotenv import load_dotenv

st.markdown("""
    <link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">
    <link href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" rel="stylesheet">
    <link rel="icon" href="https://unicons.iconscout.com/release/v4.0.8/svg/line/instagram.svg" type="image/svg+xml">

""", unsafe_allow_html=True)


st.markdown("""
    <style>
    .sidebar-section {
        margin: 20px 0;
    }
    .sidebar-section h4 {
        margin-bottom: 10px;
    }
    .sidebar-step {
        display: flex;
        align-items: center;
        margin: 8px 0;
        font-size: 15px;
    }
    .sidebar-step i {
        margin-right: 10px;
        font-size: 18px;
        color: #4dabf7;  /* nice blue */
    }
    .result-card {
        padding: 15px;
        border-radius: 8px;
        margin: 10px 0;
        font-weight: 500;
        color: white;
    }
    .result-card.fake {
        background-color: #ff4d4f;  /* Red */
        border: 1px solid #a8071a;
    }
    .result-card.real {
        background-color: #52c41a;  /* Green */
        border: 1px solid #237804;
    }
    .result-card.error {
        background-color: #faad14;  /* Amber/Yellow */
        border: 1px solid #ad6800;
    }
    .result-card .icon {
        margin-right: 8px;
    }
    </style>
""", unsafe_allow_html=True)


@st.cache_resource
def load_model():
    model_path = os.path.join(os.path.dirname(__file__), "svm_model.pkl")
    try:
        return joblib.load(model_path)
    except FileNotFoundError:
        st.error(f"❌ Model file not found at {model_path}")
        st.stop()
        
# Configure page
st.set_page_config(page_title="Instagram Fake Account Detector", page_icon="📷")

def apifyreq(username):
    try:
        load_dotenv()
        api_key = os.getenv("API_TOKEN")
        client = ApifyClient(api_key)
        run_input = {"usernames": [username]}
        actor = os.getenv("ACTOR")
        run = client.actor(actor).call(run_input=run_input)
        dataset_id = run["defaultDatasetId"]
        return client.dataset(dataset_id).list_items().items
    except Exception as e:
        print(f"Error in apifyreq: {e}")
        return None

# -------------------------------
# Data Preprocessing
# -------------------------------
def preprocess_data(dataset):
    try:
        if isinstance(dataset, list) and len(dataset) > 0:
            dataset = dataset[0]
        elif not isinstance(dataset, dict):
            return None

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
    except Exception as e:
        print(f"Error in preprocess_data: {e}")
        return None

# -------------------------------
# Prediction
# -------------------------------
def predict_fake_account(model, processed_data):
    try:
        return bool(model.predict(processed_data)[0])
    except Exception as e:
        print(f"Error in predict_fake_account: {e}")
        return None

st.set_page_config(
    page_title="FAITH",
    page_icon="https://unicons.iconscout.com/release/v4.0.8/svg/line/instagram.svg",   # placeholder, we’ll inject Instagram below
    layout="wide"
)

# Main Streamlit App
st.markdown(
    """
    <h1 style='display:flex;align-items:center;gap:10px;'>
        <i class="uil uil-instagram"></i> FAITH - Instagram Fake Account detector
    </h1>
    <hr>
    """,
    unsafe_allow_html=True
)


# Sidebar
st.sidebar.markdown("### <i class='uil uil-info-circle icon'></i> About", unsafe_allow_html=True)
st.sidebar.info(
    "This tool uses machine learning to detect fake Instagram accounts "
    "based on profile features like username patterns, bio length, follower ratios, and more."
)

st.sidebar.markdown("### <i class='uil uil-search icon'></i> How it works", unsafe_allow_html=True)
st.sidebar.markdown(
    """
    <div class='sidebar-section'>
        <h4>How it works</h4>
        <div class='sidebar-step'>
            <i class="uil uil-user-circle"></i> Enter a username
        </div>
        <div class='sidebar-step'>
            <i class="uil uil-database"></i> Data fetched from API
        </div>
        <div class='sidebar-step'>
            <i class="uil uil-brain"></i> ML model analysis
        </div>
        <div class='sidebar-step'>
            <i class="uil uil-check-circle"></i> Output: Real or Fake
        </div>
    </div>
    """,
    unsafe_allow_html=True
)


# Main input
st.subheader("Enter Instagram Username")
username = st.text_input("Username (without @):", placeholder="e.g., john_doe")

col1, col2, col3 = st.columns([1, 2, 1])
with col2:
    check_button = st.button("Check Account", use_container_width=True, type="primary")
    
fun_messages = [
    "🧠 Importing brain modules...",
    "👾 Summoning cyber intelligence...",
    "⚡ Charging neural batteries...",
    "🕹️ Powering up AI circuits...",
    "🧩 Loading pattern recognition skills..."
]

# -------------------------------
# Run Prediction
# -------------------------------
if check_button:
    if not username.strip():
        st.warning("⚠️ Please enter a username.")
    else:
        progress_bar = st.progress(0)
        status_text = st.empty()

        try:
            # Step 1: Fetch
            status_text.text("🔍 Fetching profile data...")
            progress_bar.progress(25)
            dataset = apifyreq(username)

            if not dataset:
                st.error("❌ Username not found or profile is private/inaccessible")
            else:
                # Step 2: Process
                status_text.text("⚙️ Processing data...")
                progress_bar.progress(50)
                processed = preprocess_data(dataset)

                if processed is None:
                    st.error("⚠️ Error in data preprocessing")
                else:
                    # Step 3: Load model
                    status_text.text(random.choice(fun_messages))
                    progress_bar.progress(75)
                    model = load_model()

                    # Step 4: Predict
                    status_text.text("🔮 Making prediction...")
                    progress_bar.progress(100)
                    pred = predict_fake_account(model, processed)

                    # Clear progress
                    progress_bar.empty()
                    status_text.empty()

                    # Result
                    st.markdown("---")
                    st.markdown(f"<h3><i class='uil uil-user'></i> Results for @{username}</h3>", unsafe_allow_html=True)


                    # Display results
                    if pred is None:
                        st.markdown(
                            "<div class='result-card error'>"
                            "<i class='uil uil-exclamation-octagon icon'></i>"
                            "<strong> Error during prediction</strong><br>"
                            "Something went wrong while analyzing this account."
                            "</div>",
                            unsafe_allow_html=True,
                        )
                    elif pred:
                        st.markdown(
                            "<div class='result-card fake'>"
                            "<i class='uil uil-exclamation-triangle icon'></i>"
                            "<strong> FAKE Account Detected</strong><br>"
                            "This profile shows characteristics commonly associated with fake accounts."
                            "</div>",
                            unsafe_allow_html=True,
                        )
                    else:
                        st.markdown(
                            "<div class='result-card real'>"
                            "<i class='uil uil-check-circle icon'></i>"
                            "<strong> REAL Account</strong><br>"
                            "This profile shows characteristics of an authentic account."
                            "</div>",
                            unsafe_allow_html=True,
                        )

                    with st.expander("<i class='uil uil-chart icon'></i> View Processed Features", expanded=False):
                        st.dataframe(processed)

        except Exception as e:
            progress_bar.empty()
            status_text.empty()
            st.error(f"❌ An error occurred: {str(e)}")
