function confirmLocation(location, confirmation)
{
    if (confirm(confirmation))
        window.location = location;
    else
        return false;
}