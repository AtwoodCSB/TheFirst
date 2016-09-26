<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output indent="no" omit-xml-declaration="yes" method="html"/>
<xsl:param name="attrs"/>
<xsl:template match="/">
[<xsl:apply-templates/>]
</xsl:template>

<xsl:template match="item">
{<xsl:for-each select="attribute::*">
	<xsl:if test="contains($attrs,concat(',',name(),','))">
		"<xsl:value-of select="name()"/>":"<xsl:value-of select="."/>",
	</xsl:if>
</xsl:for-each>
<xsl:if test="/list/item/item">
	"dp":"<xsl:value-of select="count(ancestor::item)"/>",
	<xsl:if test="position()=1">"pos":"first",</xsl:if>
	<xsl:if test="position()=last()">"pos":"last",</xsl:if>
</xsl:if>
},
<xsl:if test="item">
	<xsl:apply-templates select="item"/>
</xsl:if>
</xsl:template>

</xsl:stylesheet>