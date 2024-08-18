export const redirectPage = async function redirectPage() {
    await new Promise((resolve) => setTimeout(resolve, 9000)); // mirror node will have received the transaction after ±10 seconds
    // Construct the new URL without query parameters
    const cleanURL = window.location.origin + window.location.pathname + '?cache_buster=' + new Date().getTime();
    window.location.href = cleanURL;
};
