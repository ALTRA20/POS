function dateNow(){
    const currentDate = new Date();

    // Get the year, month, and day
    const year = currentDate.getFullYear();
    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Adding 1 because months are zero-indexed
    const day = currentDate.getDate().toString().padStart(2, '0');

    // Combine the components in "Y-m-d" format
    const formattedDate = `${year}-${month}-${day}`;
    return formattedDate;
}