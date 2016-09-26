<?xml version="1.0"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output indent="no" omit-xml-declaration="yes" method="html"/>
	<xsl:param name="class">treeTbl</xsl:param>
	<xsl:param name="showChkFlag">0</xsl:param>
	<xsl:param name="dataFlag"/>
	<xsl:template match="/">
	<table class="{$class}" tag="sysTree">
		<col width="300"/>
		<col width="100"/>
		<col/>
		<tr>
			<th>分类名</th>
			<th>排序号</th>
			<th>操作</th>
		</tr>
		<xsl:apply-templates select="/list/item">
			<xsl:sort select="@sortId" data-type="number" order="ascending"/>
		</xsl:apply-templates>
	</table>
	</xsl:template>

	<xsl:template match="item">
		<xsl:param name="depth">0</xsl:param>

		<xsl:variable name="treeBgClass">nPad 
			<xsl:choose>
				<xsl:when test="item">treeBg_branch_open control</xsl:when>
				<xsl:otherwise>treeBg_leaf</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>

		<xsl:variable name="treeClass">nPad 
			<xsl:choose>
				<xsl:when test="item">tree_branch_open</xsl:when>
				<xsl:otherwise>tree_leaf</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>

		<xsl:variable name="treeTextClass">
			<xsl:choose>
				<xsl:when test="item">tree_text_branch</xsl:when>
				<xsl:otherwise>tree_text_leaf</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>

		<tr dp="{$depth}">
		<td>
			<xsl:if test="$depth &gt; 0">
				<div class="nPad">
					<xsl:attribute name="style">
						width: <xsl:value-of select="$depth*20"/>px
					</xsl:attribute>
					&#160;
				</div>
			</xsl:if>
			<div class="{$treeBgClass}">&#160;</div>
			<div class="{$treeClass}">&#160;</div>
			<label class="{$treeTextClass}">
				<xsl:if test="$showChkFlag=1">
					<input type="checkbox" name="cId" id="cId{@id}"/>
				</xsl:if>
				<xsl:value-of select="@cName"/>
			</label>
		</td>
		<td><xsl:value-of select="format-number(@sortId,'0.00')"/></td>
		<td>
			<xsl:variable name="levelLimit">
				<xsl:choose>
					<xsl:when test="/list/@_levelLimit"><xsl:value-of select="/list/@_levelLimit"/></xsl:when>
					<xsl:otherwise>999</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>

			<xsl:variable name="allowAdd">
				<xsl:choose>
					<xsl:when test="ancestor-or-self::item[@_allowAdd]">0</xsl:when>
					<xsl:when test="$levelLimit &lt;= ($depth+1)">0</xsl:when>
					<xsl:otherwise>1</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>

			<xsl:variable name="allowEdit">
				<xsl:choose>
					<xsl:when test="ancestor-or-self::item[@_allowEdit=0]">0</xsl:when>
					<xsl:otherwise>1</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>

			<xsl:variable name="allowDel">
				<xsl:choose>
					<xsl:when test="ancestor-or-self::item[@_allowDel=0]">0</xsl:when>
					<xsl:otherwise>1</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>

			<xsl:if test="$allowAdd=1">
				<a href="?act=add&amp;id={@id}&amp;cName={@cName}&amp;dataFlag={$dataFlag}" popbox="1" boxTi="添加下级分类">
				添加下级分类</a>&#160;
			</xsl:if>

			<xsl:if test="$allowEdit=1">
				<a href="?act=edit&amp;id={@id}&amp;cName={@cName}&amp;dataFlag={$dataFlag}" popBox="1" boxTi="编辑分类">编辑</a>&#160;
			</xsl:if>

			<xsl:if test="$allowDel=1">
				<a href="javascript:void(0);" onclick="chkLink('?act=saveDel&amp;id={@id}&amp;dataFlag={$dataFlag}',this,'确定要删除吗?')">删除</a>
			</xsl:if>

		</td>
		</tr>
		<xsl:if test="item">
			<xsl:apply-templates>
				<xsl:with-param name="depth" select="$depth+1"/>
			</xsl:apply-templates>
		</xsl:if>
	</xsl:template>
</xsl:stylesheet>