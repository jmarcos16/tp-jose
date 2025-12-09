import * as React from "react"
import { ChevronDown } from "lucide-react"
import { cn } from "@/lib/utils"

const Select = React.forwardRef<HTMLSelectElement, React.ComponentProps<"select">>(
  ({ className, children, ...props }, ref) => {
    return (
      <div className="relative inline-flex">
        <select
          className={cn(
            "flex h-10 w-full rounded-lg glassmorphism-input glassmorphism-border pl-4 pr-10 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-[#137fec]/50 focus:border-[#137fec] disabled:cursor-not-allowed disabled:opacity-50 transition-all appearance-none cursor-pointer [&>option]:bg-[#1a1a1a] [&>option]:text-white",
            className
          )}
          ref={ref}
          {...props}
        >
          {children}
        </select>
        <ChevronDown className="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-white/70 pointer-events-none" />
      </div>
    )
  }
)
Select.displayName = "Select"

export { Select }
