//check for Navigation Timing API support
if (window.performance) {
    console.info("window.performance works fine on this browser");
  }
  console.info(performance.getEntriesByType("navigation")[0].type);
  if (performance.getEntriesByType("navigation")[0].type != performance.getEntriesByType("navigation").type) {
    console.info( "This page is reloaded" );
  } else {
    console.info( "This page is not reloaded");
  }