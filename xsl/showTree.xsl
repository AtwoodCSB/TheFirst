<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output indent="no" omit-xml-declaration="yes" method="html"/>
	<xsl:param name="class">tree</xsl:param>
	<xsl:template match="/">
	<dl class="{$class}" tag="sysTree" type="1">
		<xsl:apply-templates/>
	</dl>
	</xsl:template>
	<xsl:template match="item">
		<xsl:param name="depth">0</xsl:param>
		<dt>
			<div tag="control">
				<xsl:attribute name="class">nPad 
					<xsl:choose>
						<xsl:when test="position()=1 and item and $depth=0">treeBg_branch_open_first</xsl:when>
						<xsl:when test="(position()!=last() and item and $depth&gt;0) or (position()!=1 and position()!=last() and item and $depth=0)">treeBg_branch_open</xsl:when>
						<xsl:when test="position()=last() and item">treeBg_branch_open_last</xsl:when>
						<xsl:when test="position()=1 and not(item) and $depth=0">treeBg_leaf_first</xsl:when>
						<xsl:when test="(position()!=last() and not(item) and $depth&gt;0) or (position()!=1 and position()!=last() and not(item) and $depth=0)">treeBg_leaf</xsl:when>
						<xsl:when test="position()=last() and not(item)">treeBg_leaf_last</xsl:when>
					</xsl:choose>
				</xsl:attribute>
				<xsl:value-of select="'&amp;nbsp;'" disable-output-escaping="yes"/>
			</div>
			<div>
				<xsl:attribute name="class">nPad 
					<xsl:choose>
						<xsl:when test="item">tree_branch_open</xsl:when>
						<xsl:otherwise>tree_leaf</xsl:otherwise>
					</xsl:choose>
				</xsl:attribute>
				<xsl:value-of select="'&amp;nbsp;'" disable-output-escaping="yes"/>
			</div>
			<div>
				<xsl:attribute name="class">nText 
					<xsl:choose>
						<xsl:when test="item">tree_text_branch</xsl:when>
						<xsl:otherwise>tree_text_leaf</xsl:otherwise>
					</xsl:choose>
				</xsl:attribute>
				<label>
					<input type="checkbox" name="cId" id="cId{@id}"/>
					<xsl:value-of select="@cName"/>
				</label>
			</div>
			<br class="clearFix"/>
		</dt>
		<xsl:if test="item">
			<dd>
				<xsl:if test="following-sibling::item">
					<xsl:attribute name="class">
					     line
					</xsl:attribute>
				</xsl:if>
				<dl>
					<xsl:apply-templates>
						<xsl:with-param name="depth" select="$depth+1"/>
					</xsl:apply-templates>
				</dl>
			</dd>
		</xsl:if>
	</xsl:template>
</xsl:stylesheet>