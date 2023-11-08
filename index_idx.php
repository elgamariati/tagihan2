<html>
<body>


<select id="filter_fakultas">
  <option value="">(any)</option>
  <option value="1">FKIP</option>
  <option value="2">Hukum</option>
</select>
<br />
<iframe id="embedded_report" border="0" frameborder="0" 
    width="800"
    height="600"
    src="http://git.ulm.ac.id:8082/public/report/f42cd8feef7848b69103e830964a3a56"></iframe>

</body>
<script>
    window.embeddedReportOriginalUrl = null;
    window.refreshEmbeddedReport = function() {
    var iframe = document.getElementById('embedded_report');
    if (!embeddedReportOriginalUrl)
        embeddedReportOriginalUrl = iframe.getAttribute('src');
    var reportParams = {};
    reportParams['filter_fakultas'] = document.getElementById('filter_fakultas').value;
    iframe.setAttribute('src', embeddedReportOriginalUrl + "?report_parameters="+JSON.stringify(reportParams))};

    document.getElementById('filter_fakultas').onchange = refreshEmbeddedReport;
</script>
</html>
