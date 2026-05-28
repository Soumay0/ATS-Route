import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import LanguageDetector from 'i18next-browser-languagedetector';

const resources = {
  en: {
    translation: {
      "dashboard": "Dashboard",
      "welcome_aboard": "Welcome Aboard",
      "welcome_msg": "You're successfully logged into the AeroRoute network. Use the navigation to access the live ATS map and radar tools.",
      "active_flights": "Active Global Flights",
      "network_status": "Network Status",
      "active_weather": "Active Weather Alerts",
      "recent_flights": "Recent Flights Tracker",
      "callsign": "Callsign",
      "origin_country": "Origin Country",
      "altitude": "Altitude",
      "speed": "Speed",
      "status": "Status",
      "in_air": "In Air",
      "on_ground": "On Ground",
      "launch_map": "Launch ATS Network Map",
      "slot_management": "View Slot Management",
      "check_weather": "Check Live Weather",
      "ats_network": "ATS Network",
      "profile": "Profile",
      "logout": "Log Out"
    }
  },
  hi: { // Hindi
    translation: {
      "dashboard": "डैशबोर्ड",
      "welcome_aboard": "स्वागत है",
      "welcome_msg": "आप एयरोरूट नेटवर्क में सफलतापूर्वक लॉग इन हैं। लाइव एटीएस मैप और रडार टूल तक पहुंचने के लिए नेविगेशन का उपयोग करें।",
      "active_flights": "सक्रिय वैश्विक उड़ानें",
      "network_status": "नेटवर्क स्थिति",
      "active_weather": "सक्रिय मौसम अलर्ट",
      "recent_flights": "हाल की उड़ानें ट्रैकर",
      "callsign": "कॉलसाइन",
      "origin_country": "मूल देश",
      "altitude": "ऊंचाई",
      "speed": "गति",
      "status": "स्थिति",
      "in_air": "हवा में",
      "on_ground": "जमीन पर",
      "launch_map": "एटीएस नेटवर्क मैप लॉन्च करें",
      "slot_management": "स्लॉट प्रबंधन देखें",
      "check_weather": "लाइव मौसम की जाँच करें",
      "ats_network": "एटीएस नेटवर्क",
      "profile": "प्रोफ़ाइल",
      "logout": "लॉग आउट"
    }
  },
  bn: { // Bengali
    translation: {
      "dashboard": "ড্যাশবোর্ড",
      "welcome_aboard": "স্বাগতম",
      "welcome_msg": "আপনি সফলভাবে এরোরুট নেটওয়ার্কে লগ ইন করেছেন। লাইভ এটিএস ম্যাপ এবং রাডার টুল অ্যাক্সেস করতে নেভিগেশন ব্যবহার করুন।",
      "active_flights": "সক্রিয় গ্লোবাল ফ্লাইট",
      "network_status": "নেটওয়ার্ক স্ট্যাটাস",
      "active_weather": "সক্রিয় আবহাওয়া সতর্কতা",
      "recent_flights": "সাম্প্রতিক ফ্লাইট ট্র্যাকার",
      "callsign": "কলসাইন",
      "origin_country": "উৎস দেশ",
      "altitude": "উচ্চতা",
      "speed": "গতি",
      "status": "স্ট্যাটাস",
      "in_air": "আকাশে",
      "on_ground": "ভূমিতে",
      "launch_map": "এটিএস নেটওয়ার্ক ম্যাপ চালু করুন",
      "slot_management": "স্লট ম্যানেজমেন্ট দেখুন",
      "check_weather": "লাইভ আবহাওয়া চেক করুন",
      "ats_network": "এটিএস নেটওয়ার্ক",
      "profile": "প্রোফাইল",
      "logout": "লগ আউট"
    }
  },
  te: { // Telugu
    translation: {
      "dashboard": "డాష్‌బోర్డ్",
      "welcome_aboard": "స్వాగతం",
      "welcome_msg": "మీరు ఏరోరూట్ నెట్‌వర్క్‌కి విజయవంతంగా లాగిన్ అయ్యారు. లైవ్ ఏటీఎస్ మ్యాప్ మరియు రాడార్ టూల్స్ యాక్సెస్ చేయడానికి నావిగేషన్ ఉపయోగించండి.",
      "active_flights": "క్రియాశీల గ్లోబల్ విమానాలు",
      "network_status": "నెట్‌వర్క్ స్థితి",
      "active_weather": "క్రియాశీల వాతావరణ హెచ్చరికలు",
      "recent_flights": "ఇటీవలి విమానాల ట్రాకర్",
      "callsign": "కాల్‌సైన్",
      "origin_country": "మూల దేశం",
      "altitude": "ఎత్తు",
      "speed": "వేగం",
      "status": "స్థితి",
      "in_air": "గాలిలో",
      "on_ground": "నేలపై",
      "launch_map": "ఏటీఎస్ నెట్‌వర్క్ మ్యాప్ ప్రారంభించండి",
      "slot_management": "స్లాట్ నిర్వహణ చూడండి",
      "check_weather": "లైవ్ వాతావరణం తనిఖీ చేయండి",
      "ats_network": "ఏటీఎస్ నెట్‌వర్క్",
      "profile": "ప్రొఫైల్",
      "logout": "లాగ్ అవుట్"
    }
  },
  mr: { // Marathi
    translation: {
      "dashboard": "डॅशबोर्ड",
      "welcome_aboard": "स्वागत आहे",
      "welcome_msg": "आपण एयरोरूट नेटवर्कवर यशस्वीरित्या लॉग इन आहात. थेट एटीएस नकाशे आणि रडार साधने प्रवेश करण्यासाठी नेव्हिगेशन वापरा.",
      "active_flights": "सक्रिय जागतिक उड्डाणे",
      "network_status": "नेटवर्क स्थिती",
      "active_weather": "सक्रिय हवामान सूचना",
      "recent_flights": "अलीकडील उड्डाणे ट्रॅकर",
      "callsign": "कॉलसाइन",
      "origin_country": "मूळ देश",
      "altitude": "उंची",
      "speed": "वेग",
      "status": "स्थिती",
      "in_air": "हवेत",
      "on_ground": "जमिनीवर",
      "launch_map": "एटीएस नेटवर्क नकाशा लाँच करा",
      "slot_management": "स्लॉट व्यवस्थापन पहा",
      "check_weather": "थेट हवामान तपासा",
      "ats_network": "एटीएस नेटवर्क",
      "profile": "प्रोफाईल",
      "logout": "लॉग आउट"
    }
  },
  ta: { // Tamil
    translation: {
      "dashboard": "டாஷ்போர்டு",
      "welcome_aboard": "வரவேற்கிறோம்",
      "welcome_msg": "நீங்கள் ஏரோரூட் நெட்வொர்க்கில் வெற்றிகரமாக உள்நுழைந்துள்ளீர்கள். நேரடி ஏடிஎஸ் வரைபடம் மற்றும் ரேடார் கருவிகளை அணுக வழிசெலுத்தலைப் பயன்படுத்தவும்.",
      "active_flights": "செயலில் உள்ள உலகளாவிய விமானங்கள்",
      "network_status": "நெட்வொர்க் நிலை",
      "active_weather": "செயலில் உள்ள வானிலை எச்சரிக்கைகள்",
      "recent_flights": "சமீபத்திய விமானங்கள் டிராக்கர்",
      "callsign": "அழைப்பு குறியீடு",
      "origin_country": "தோற்ற நாடு",
      "altitude": "உயரம்",
      "speed": "வேகம்",
      "status": "நிலை",
      "in_air": "காற்றில்",
      "on_ground": "தரையில",
      "launch_map": "ஏடிஎஸ் நெட்வொர்க் வரைபடத்தைத் தொடங்கவும்",
      "slot_management": "ஸ்லாட் நிர்வாகத்தைக் காண்க",
      "check_weather": "நேரடி வானிலையை சரிபார்க்கவும்",
      "ats_network": "ஏடிஎஸ் நெட்வொர்க்",
      "profile": "சுயவிவரம்",
      "logout": "வெளியேறு"
    }
  },
  gu: { // Gujarati
    translation: {
      "dashboard": "ડેશબોર્ડ",
      "welcome_aboard": "સ્વાગત છે",
      "welcome_msg": "તમે એરોરુટ નેટવર્કમાં સફળતાપૂર્વક લોગ ઇન કર્યું છે. લાઇવ એટીએસ નકશા અને રડાર ટૂલ્સનો ઉપયોગ કરવા માટે નેવિગેશનનો ઉપયોગ કરો.",
      "active_flights": "સક્રિય વૈશ્વિક ફ્લાઇટ્સ",
      "network_status": "નેટવર્ક સ્થિતિ",
      "active_weather": "સક્રિય હવામાન ચેતવણીઓ",
      "recent_flights": "તાજેતરની ફ્લાઇટ્સ ટ્રેકર",
      "callsign": "કોલસાઇન",
      "origin_country": "મૂળ દેશ",
      "altitude": "ઊંચાઈ",
      "speed": "ઝડપ",
      "status": "સ્થિતિ",
      "in_air": "હવામાં",
      "on_ground": "જમીન પર",
      "launch_map": "એટીએસ નેટવર્ક નકશો લોન્ચ કરો",
      "slot_management": "સ્લોટ મેનેજમેન્ટ જુઓ",
      "check_weather": "લાઇવ હવામાન તપાસો",
      "ats_network": "એટીએસ નેટવર્ક",
      "profile": "પ્રોફાઇલ",
      "logout": "લોગ આઉટ"
    }
  },
  kn: { // Kannada
    translation: {
      "dashboard": "ಡ್ಯಾಶ್ಬೋರ್ಡ್",
      "welcome_aboard": "ಸ್ವಾಗತ",
      "welcome_msg": "ನೀವು ಏರೋರೂಟ್ ನೆಟ್‌ವರ್ಕ್‌ಗೆ ಯಶಸ್ವಿಯಾಗಿ ಲಾಗ್ ಇನ್ ಆಗಿದ್ದೀರಿ. ಲೈವ್ ಎಟಿಎಸ್ ನಕ್ಷೆ ಮತ್ತು ರೇಡಾರ್ ಪರಿಕರಗಳನ್ನು ಪ್ರವೇಶಿಸಲು ನ್ಯಾವಿಗೇಷನ್ ಬಳಸಿ.",
      "active_flights": "ಸಕ್ರಿಯ ಜಾಗತಿಕ ವಿಮಾನಗಳು",
      "network_status": "ನೆಟ್‌ವರ್ಕ್ ಸ್ಥಿತಿ",
      "active_weather": "ಸಕ್ರಿಯ ಹವಾಮಾನ ಎಚ್ಚರಿಕೆಗಳು",
      "recent_flights": "ಇತ್ತೀಚಿನ ವಿಮಾನಗಳ ಟ್ರ್ಯಾಕರ್",
      "callsign": "ಕಾಲ್ಸೈನ್",
      "origin_country": "ಮೂಲ ದೇಶ",
      "altitude": "ಎತ್ತರ",
      "speed": "ವೇಗ",
      "status": "ಸ್ಥಿತಿ",
      "in_air": "ಗಾಳಿಯಲ್ಲಿ",
      "on_ground": "ನೆಲದ ಮೇಲೆ",
      "launch_map": "ಎಟಿಎಸ್ ನೆಟ್‌ವರ್ಕ್ ನಕ್ಷೆಯನ್ನು ಪ್ರಾರಂಭಿಸಿ",
      "slot_management": "ಸ್ಲಾಟ್ ನಿರ್ವಹಣೆ ವೀಕ್ಷಿಸಿ",
      "check_weather": "ಲೈವ್ ಹವಾಮಾನ ಪರಿಶೀಲಿಸಿ",
      "ats_network": "ಎಟಿಎಸ್ ನೆಟ್‌ವರ್ಕ್",
      "profile": "ಪ್ರೊಫೈಲ್",
      "logout": "ಲಾಗ್ ಔಟ್"
    }
  },
  or: { // Odia
    translation: {
      "dashboard": "ଡ୍ୟାସବୋର୍ଡ",
      "welcome_aboard": "ସ୍ୱାଗତମ",
      "welcome_msg": "ଆପଣ ଏରୋରୁଟ୍ ନେଟୱାର୍କକୁ ସଫଳତାର ସହ ଲଗ୍ ଇନ୍ କରିଛନ୍ତି। ଲାଇଭ୍ ଏଟିଏସ୍ ମ୍ୟାପ୍ ଏବଂ ରାଡାର୍ ଟୁଲ୍ସ ବ୍ୟବହାର କରିବାକୁ ନେଭିଗେସନ୍ ବ୍ୟବହାର କରନ୍ତୁ।",
      "active_flights": "ସକ୍ରିୟ ବିଶ୍ୱବ୍ୟାପୀ ଫ୍ଲାଇଟ୍",
      "network_status": "ନେଟୱାର୍କ ସ୍ଥିତି",
      "active_weather": "ସକ୍ରିୟ ପାଣିପାଗ ସତର୍କତା",
      "recent_flights": "ସାମ୍ପ୍ରତିକ ଫ୍ଲାଇଟ୍ ଟ୍ରାକର୍",
      "callsign": "କଲ୍ ସାଇନ୍",
      "origin_country": "ଉତ୍ସ ଦେଶ",
      "altitude": "ଉଚ୍ଚତା",
      "speed": "ଗତି",
      "status": "ସ୍ଥିତି",
      "in_air": "ଆକାଶରେ",
      "on_ground": "ଭୂମିରେ",
      "launch_map": "ଏଟିଏସ୍ ନେଟୱାର୍କ ମ୍ୟାପ୍ ଲଞ୍ଚ କରନ୍ତୁ",
      "slot_management": "ସ୍ଲଟ୍ ପରିଚାଳନା ଦେଖନ୍ତୁ",
      "check_weather": "ଲାଇଭ୍ ପାଣିପାଗ ଯାଞ୍ଚ କରନ୍ତୁ",
      "ats_network": "ଏଟିଏସ୍ ନେଟୱାର୍କ",
      "profile": "ପ୍ରୋଫାଇଲ୍",
      "logout": "ଲଗ୍ ଆଉଟ୍"
    }
  },
  ml: { // Malayalam
    translation: {
      "dashboard": "ഡാഷ്ബോർഡ്",
      "welcome_aboard": "സ്വാഗതം",
      "welcome_msg": "നിങ്ങൾ എയ്‌റോറൂട്ട് നെറ്റ്‌വർക്കിലേക്ക് വിജയകരമായി ലോഗിൻ ചെയ്തു. തത്സമയ എടിഎസ് മാപ്പും റഡാർ ഉപകരണങ്ങളും ആക്സസ് ചെയ്യാൻ നാവിഗേഷൻ ഉപയോഗിക്കുക.",
      "active_flights": "സജീവമായ ആഗോള ഫ്ലൈറ്റുകൾ",
      "network_status": "നെറ്റ്‌വർക്ക് നില",
      "active_weather": "സജീവമായ കാലാവസ്ഥാ മുന്നറിയിപ്പുകൾ",
      "recent_flights": "സമീപകാല ഫ്ലൈറ്റുകളുടെ ട്രാക്കർ",
      "callsign": "കോൾസൈൻ",
      "origin_country": "ഉത്ഭവ രാജ്യം",
      "altitude": "ഉയരം",
      "speed": "വേഗത",
      "status": "അവസ്ഥ",
      "in_air": "ആകാശത്ത്",
      "on_ground": "നിലത്ത്",
      "launch_map": "എടിഎസ് നെറ്റ്‌വർക്ക് മാപ്പ് സമാരംഭിക്കുക",
      "slot_management": "സ്ലോട്ട് മാനേജ്മെന്റ് കാണുക",
      "check_weather": "തത്സമയ കാലാവസ്ഥ പരിശോധിക്കുക",
      "ats_network": "എടിഎസ് നെറ്റ്‌വർക്ക്",
      "profile": "പ്രൊഫൈൽ",
      "logout": "ലോഗ് ഔട്ട്"
    }
  },
  pa: { // Punjabi
    translation: {
      "dashboard": "ਡੈਸ਼ਬੋਰਡ",
      "welcome_aboard": "ਸਵਾਗਤ ਹੈ",
      "welcome_msg": "ਤੁਸੀਂ ਏਰੋਰੂਟ ਨੈੱਟਵਰਕ ਵਿੱਚ ਸਫਲਤਾਪੂਰਵਕ ਲੌਗਇਨ ਹੋ। ਲਾਈਵ ਏਟੀਐਸ ਮੈਪ ਅਤੇ ਰਾਡਾਰ ਟੂਲਸ ਤੱਕ ਪਹੁੰਚ ਕਰਨ ਲਈ ਨੈਵੀਗੇਸ਼ਨ ਦੀ ਵਰਤੋਂ ਕਰੋ।",
      "active_flights": "ਸਰਗਰਮ ਗਲੋਬਲ ਉਡਾਣਾਂ",
      "network_status": "ਨੈੱਟਵਰਕ ਸਥਿਤੀ",
      "active_weather": "ਸਰਗਰਮ ਮੌਸਮ ਚੇਤਾਵਨੀਆਂ",
      "recent_flights": "ਤਾਜ਼ਾ ਉਡਾਣਾਂ ਟ੍ਰੈਕਰ",
      "callsign": "ਕਾਲਸਾਈਨ",
      "origin_country": "ਮੂਲ ਦੇਸ਼",
      "altitude": "ਉਚਾਈ",
      "speed": "ਗਤੀ",
      "status": "ਸਥਿਤੀ",
      "in_air": "ਹਵਾ ਵਿੱਚ",
      "on_ground": "ਜ਼ਮੀਨ 'ਤੇ",
      "launch_map": "ਏਟੀਐਸ ਨੈੱਟਵਰਕ ਮੈਪ ਲਾਂਚ ਕਰੋ",
      "slot_management": "ਸਲਾਟ ਪ੍ਰਬੰਧਨ ਵੇਖੋ",
      "check_weather": "ਲਾਈਵ ਮੌਸਮ ਦੀ ਜਾਂਚ ਕਰੋ",
      "ats_network": "ਏਟੀਐਸ ਨੈੱਟਵਰਕ",
      "profile": "ਪ੍ਰੋਫਾਈਲ",
      "logout": "ਲੌਗ ਆਉਟ"
    }
  }
};

i18n
  .use(LanguageDetector)
  .use(initReactI18next)
  .init({
    resources,
    fallbackLng: 'en',
    interpolation: {
      escapeValue: false // react already safes from xss
    }
  });

export default i18n;
