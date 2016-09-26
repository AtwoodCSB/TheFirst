<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output indent="no" omit-xml-declaration="no" method="xml" encoding="utf-8"/>
<xsl:template match="/">
	<list>
		<xsl:apply-templates select="/list/item">
			<xsl:sort select="@sortId" data-type="number" order="ascending"/>
		</xsl:apply-templates>
	</list>
</xsl:template>

<xsl:template match="item">
	<xsl:copy>
		<xsl:for-each select="attribute::*">
			<xsl:if test="not(contains(',parentId,sortId,maxSortId,',concat(',',name(),',')))">
				<xsl:copy/>
			</xsl:if>
		</xsl:for-each>
		<xsl:apply-templates select="item">
			<xsl:sort select="@sortId" data-type="number" order="ascending"/>
		</xsl:apply-templates>
	</xsl:copy>
</xsl:template>

</xsl:stylesheet>