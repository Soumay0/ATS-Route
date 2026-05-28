import React from 'react';

export default function MapLegend() {
    return (
        <div className="absolute bottom-6 left-6 z-[400] bg-white/90 dark:bg-black/60 backdrop-blur px-4 py-3 rounded-lg border border-border-light shadow-xl">
            <h4 className="text-sm font-bold mb-2 border-b border-border-light pb-1">Legend</h4>
            <div className="flex flex-col gap-2">
                <div className="flex items-center gap-3 text-xs font-mono">
                    <div className="w-3 h-3 bg-accent-primary rounded-full shadow-[0_0_8px_rgba(2,132,199,0.5)] dark:shadow-[0_0_8px_rgba(0,240,255,0.5)] border border-white"></div>
                    <span>Waypoint (VOR/NDB)</span>
                </div>
                <div className="flex items-center gap-3 text-xs font-mono">
                    <div className="w-4 h-[2px] bg-[#0284c7] dark:bg-[#00f0ff]"></div>
                    <span>Active Route</span>
                </div>
                <div className="flex items-center gap-3 text-xs font-mono">
                    <div className="w-4 h-[4px] bg-[#e11d48] dark:bg-[#ff00a0] border-dashed border-t-2 border-transparent"></div>
                    <span>Congested Route</span>
                </div>
                <div className="flex items-center gap-3 text-xs font-mono">
                    <div className="text-text-primary">✈</div>
                    <span>Live Flight</span>
                </div>
            </div>
        </div>
    );
}
