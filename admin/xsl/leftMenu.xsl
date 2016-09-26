<?xml version='1.0'?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output indent="no" omit-xml-declaration="no" method="html" encoding="utf-8"/>
<xsl:template match="root">
	<dl class="menu">
		<xsl:apply-templates select="list"/>
	</dl>
</xsl:template>


<xsl:template match="list">
	<dt><xsl:value-of select="@txt"/></dt>
	<dd>
	<ul>
		<xsl:apply-templates select="item"/>
	</ul>
	</dd>
</xsl:template>

<xsl:template match="item">
	<a href="{@url}" target="iframePage"><xsl:value-of select="@txt"/></a>
</xsl:template>

</xsl:stylesheet>