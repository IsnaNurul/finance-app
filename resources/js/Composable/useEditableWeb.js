import axios from "axios";

const useEditableWeb = () => {
    const changeBackgroundImage = (htmlString, image) => {
        if (!htmlString) return htmlString;
        const parser = new DOMParser();
        const doc = parser.parseFromString(htmlString, 'text/html');
        const target = doc.querySelector('.eventizy__slide div[style*="background-image"]');
        if (target) {
            target.style.backgroundImage = `url("${image}")`;
        }
        return doc.body.innerHTML;
    };


    const loadDesignConfig = (url) => {
        axios.get(url).then((res) => {
            tailwind.config.theme.extend.colors = res.data.colors;
        });
    };


    return { changeBackgroundImage,loadDesignConfig };
};

export default useEditableWeb;
