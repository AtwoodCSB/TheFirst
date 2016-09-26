<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output indent="no" omit-xml-declaration="no" method="xml" encoding="utf-8"/>
<xsl:param name="attrs"/>

<xsl:template match="/">
<list>
	<xsl:apply-templates/>
</list>
</xsl:template>

<xsl:template match="item">
<item>
	<xsl:for-each select="attribute::*">
		<xsl:if test="contains($attrs,concat(',',name(),','))">
			<xsl:copy/>
		</xsl:if>
	</xsl:for-each>
	<xsl:if test="/list/item/item">
		<xsl:attribute name="dp">
			<xsl:value-of select="count(ancestor::item)"/>
		</xsl:attribute>
	</xsl:if>
</item>
<xsl:if test="item">
	<xsl:apply-templates select="item"/>
</xsl:if>
</xsl:template>

</xsl:stylesheet>