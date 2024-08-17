export const formatTimestamp = function formatTimestamp(secondsNanoseconds) {
    // Split the seconds and nanoseconds
    const [seconds, nanoseconds] = secondsNanoseconds.split('.').map(Number);

    // Convert seconds and nanoseconds to milliseconds
    const milliseconds = seconds * 1000 + nanoseconds / 1e6;

    // Create a Date object from milliseconds
    const date = new Date(milliseconds);

    // Format the date into "Monthname Fullyear" without the day number and comma
    const options = { year: 'numeric', month: 'long' };
    const formattedDate = date.toLocaleDateString(undefined, options).replace(',', '');

    return formattedDate;
};
